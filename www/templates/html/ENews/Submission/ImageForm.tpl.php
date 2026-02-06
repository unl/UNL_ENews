<form id="enewsImage" name="enewsImage" class="dcf-form" action="#" method="post" enctype="multipart/form-data">
<input type="hidden" name="_type" value="file" />
<?php $csrf = UNL_ENews_Controller::getCSRFHelper() ?>
<input type="hidden" name="<?php echo $csrf->getTokenNameKey() ?>" value="<?php echo $csrf->getTokenName() ?>" />
<input type="hidden" name="<?php echo $csrf->getTokenValueKey() ?>" value="<?php echo $csrf->getTokenValue() ?>">

<fieldset>
    <ol class="dcf-list-bare dcf-p-0 dcf-mt-0">
        <li class="dcf-form-group">
            <label class="dcf-label" for="image">Image <span class="dcf-form-help">Displayed with your item</span></label>
            <input
                class="dcf-input-file"
                id="image"
                name="image"
                type="file"
                accept="image/jpeg, image/png"
                aria-describedby="image-input-help"
            >
            <p class="dcf-form-help" id="image-input-help">
                Accepts <code>.jpg</code>, <code>.png</code>. Maximum file upload size is 1<abbr title="Megabytes">MB</abbr>.
            </p>
            <div id="image-input-error" class="dcf-d-none dcf-rounded unl-bg-scarlet unl-cream dcf-pt-4 dcf-pb-4 dcf-pl-2 dcf-pr-2 dcf-mt-3 dcf-txt-center dcf-w-fit-content">
                The file is too large!
            </div>
        </li>
        <li class="dcf-form-group">
            <label class="dcf-label" for="file_description" id="img_description_label">Image Description
            <?php
            $disabled = 'disabled="disabled"';
            if (!empty($originalImage)) {
                $disabled = 'class="dcf-required"';
                echo '<span class="dcf-required">*</span>';
            }
            ?>
            <span class="dcf-form-help">Used as a caption on the web view</span></label>
            <input class="dcf-w-100%" id="file_description" name="file_description" <?php echo $disabled; ?> type="text" value="<?php echo getValue($originalImage, 'description'); ?>" />
        </li>
    </ol>
    
    <div id="upload_area">
    <?php if ($id && $thumbnail = UNL_ENews_Story::getByID($id)->getFileByUse('thumbnail')) : ?>
        <span id="currentThumbnail" style="cursor: pointer !important;">
            <img src="<?php echo $thumbnail->getURL(); ?>" alt="Current thumbnail" style="cursor: pointer !important;" />
            <br>
            <span class="dcf-form-help">Current Thumbnail – Click to Edit</span>
        </span>
    <?php else : ?>
        <div>Image preview</div>
    <?php endif; ?>
    </div>
    <ul id="imageControls">
        <li id="cropMessage">Click and drag on the image above to select a thumbnail</li>
        <li id="cropRatio" class="r43">Change Crop Ratio</li>
    </ul>
</fieldset>
</form>

<script type="module" defer>
    const imageInput = document.getElementById('image');
    const imageError = document.getElementById('image-input-error');
    const fileSizeLimit = 1048576; //That is 1M in bytes

    imageInput.addEventListener('change', () => {
        imageError.classList.add('dcf-d-none');

        // There is no file so we are good
        if (imageInput.files.length === 0) {
            return;
        }

        // File is too big do not keep it and show error
        if (imageInput.files[0].size > fileSizeLimit) {
            const filename = imageInput.files[0].name;
            imageError.classList.remove('dcf-d-none');
            imageError.innerText = `The file '${filename}' is too large. Please reduce the size of the image under 1 MB and try again.`;
        }
    });
</script>
