<h1>Upload photos of <?php echo $child['c_name']; ?> from your computer</h1>

<p>
  Select the photos you'd like to upload and we'll begin transfering them immediately.
</p>

<p>
  <span id="span-button-placeholder"></span>
  <button id="button-select-photos"><div>Select photos</div></button>
  <button id="button-view-page"><div>View <?php echo posessive($child['c_name']); ?> page</div></button>
</p>

<p>
  <ul id="upload-queue"></ul>
</p>
