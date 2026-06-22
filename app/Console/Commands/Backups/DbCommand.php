<?php

namespace App\Console\Commands\Backups;

use App\Services\BackupDiskResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class DbCommand extends Command
{

  protected $signature = 'backup:dbbackup';

  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle(BackupDiskResolver $resolver)
  {
    $connectionName = config('database.default');
    $db = config("database.connections.$connectionName");
    $filename = $db['database'] . '-' . now()->format('Y-m-d-H-i-s') . '.sql.enc';
    $disk = $resolver->resolve();

    $process = new Process([
      'mysqldump', 
      '--user=' . $db['username'], 
      '--host=' . $db['host'], 
      '--port=' . ($db['port'] ?? 3306),
      $db['database'],
      ]);

    $process->setEnv(['MYSQL_PWD' => $db['password']]);
    $process->run();

    if (! $process->isSuccessful()) {
      $this->error($process->getErrorOutput());
      return self::FAILURE;
    }
    $sql = $process->getOutput();
  
    $encrypted = Crypt::encryptString($sql);

    $disk->put($filename, base64_encode($encrypted));

    $this->info('Encrypted database backup created: ' . $filename);
    return self::SUCCESS;
  }
}
