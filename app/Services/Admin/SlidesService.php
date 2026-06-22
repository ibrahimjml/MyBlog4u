<?php

namespace App\Services\Admin;

use App\DTOs\Admin\SlideDTO;
use App\Enums\SlidesStatus;
use App\Models\Slide;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SlidesService
{
  use ImageUploadTrait;

     public function create(SlideDTO $dto, int $userId): Slide
    {
        return DB::transaction(function () use ($dto, $userId) {
            $imagePath = $dto->image
                ? $this->uploadImageSlide($dto->image, auth()->user()->username)
                : null;

            $data = [
                'title'       => $dto->title,
                'description' => $dto->description,
                'link'        => $dto->link,
                'status'      => $dto->status,
                'image_path'  => $imagePath,
            ];

            $data = $this->handleStatus($data, $dto, $userId);

            return Slide::create($data);
        });
    }

     public function update(Slide $slide, SlideDTO $dto, int $userId): Slide
    {
        return DB::transaction(function () use ($slide, $dto, $userId) {
            $updateData = [
                'title'       => $dto->title,
                'description' => $dto->description,
                'link'        => $dto->link,
                'status'      => $dto->status,
            ];

            if ($dto->image instanceof UploadedFile) {

                $this->deleteSlideImage($slide->image_path);
                $imagePath = $this->uploadImageSlide($dto->image, auth()->user()->username);
                $updateData['image_path'] = $imagePath;
            }

            $updateData = $this->handleStatus($updateData, $dto,$userId);

            $slide->update($updateData);

            return $slide->refresh();
        });
    }
       public function deleteSlide(Slide $slide): bool
    {
        
        if ($slide->image_path) {
            $this->deleteSlideImage($slide->image_path);
        }

        return $slide->delete();
    }
   
     protected function handleStatus(array $data, SlideDTO $dto, int $userId): array
    {
        switch ($dto->status) {
            case SlidesStatus::PUBLISHED:
                $data['published_by'] = $userId;
                $data['published_at'] = now();
                $data['disabled_by']  = null;
                $data['disabled_at']  = null;
                break;

            case SlidesStatus::DISABLED:
                $data['disabled_by']  = $userId;
                $data['disabled_at']  = now();
                $data['published_by'] = null;
                $data['published_at'] = null;
                break;
        }

        return $data;
    }

    protected function deleteSlideImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk(media_driver())->exists('slides/'.$imagePath)) {
            Storage::disk(media_driver())->delete('slides/'.$imagePath);
        }
    }
    
     public function getSlides(int $perPage = 7)
    {
        return Slide::orderBy('published_at', 'desc')->paginate($perPage);
    }
}
