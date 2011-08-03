"stories":
<?php
$stories = array();
foreach ($context as $story) {
    /* @var $story UNL_ENews_Story */
    $story_data = $story->toArray();
    $files_data = array();
    foreach ($story->getFiles() as $id=>$file) {
        $file_data = $file->toArray();
        unset($file_data['data']);
        $files_data[$file->getURL()] = $file_data;
    }
    $story_data['files'] = $files_data;
    $stories[$story->getURL()] = $story_data;
}
echo json_encode($stories);
?>