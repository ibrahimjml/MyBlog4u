import 'tinymce/tinymce';
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/content/default/content.min.css';
import 'tinymce/skins/content/default/content.css';
import 'tinymce/icons/default/icons';
import 'tinymce/themes/silver/theme';
import 'tinymce/models/dom/model';
import 'tinymce/plugins/image';
import 'tinymce/plugins/code';
import 'tinymce/plugins/codesample';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/media';

export function initTinyMCE() {
  tinymce.init({
    selector: '#textarea',
    plugins: 'code codesample image link lists media',
    codesample_global_prismjs: true,
    codesample_dialog_width: 600,
    codesample_dialog_height: 425,
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code codesample ',
    license_key: 'gpl',
    sandbox_iframes: false,
    images_upload_credentials: true,

    images_upload_handler: async function (blobInfo) {
      const formData = new FormData();
      formData.append('tiny-image', blobInfo.blob(), blobInfo.filename());

      const response = await fetch('/upload-image', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
      });

      let result;

      try {
        result = await response.json();
      } catch (e) {
        throw new Error('Upload failed: invalid JSON response');
      }

      if (!response.ok) {
        throw new Error(result?.error || `Upload failed with status ${response.status}`);
      }

      if (!result || typeof result.location !== 'string') {
        throw new Error('Invalid response format from server');
      }

      return result.location;
    },

    setup(editor) {
      editor.on('ExecCommand', function (e) {
        if (e.command === 'mceCodeSample') {
          editor.focus();
        }
      });

      editor.on("keydown", function (e) {
        if ((e.keyCode === 8 || e.keyCode === 46) && tinymce.activeEditor.selection) {
          const selectedNode = tinymce.activeEditor.selection.getNode();
          if (selectedNode && selectedNode.nodeName === 'IMG') {
            const imageSrc = selectedNode.getAttribute('src');

            if (imageSrc) {
              fetch('/image-delete', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ image: imageSrc })
              }).then(response => {
                if (!response.ok) {
                  console.error('Failed to delete image');
                }
                toastr.options = {
                  "closeButton": true,
                  "progressBar": true,
                  "positionClass": "toast-top-right",
                  "timeOut": 1000
                };
                toastr.success(`selected ${imageSrc} deleted`);
              }).catch(error => {
                console.error('Error:', error);
              });
            }
          }
        }
      });
    },

    content_css: ['/tinymce.css'],
    document_base_url: '/',
    relative_urls: false,
    remove_script_host: false,
    skin: false,
  });
}
