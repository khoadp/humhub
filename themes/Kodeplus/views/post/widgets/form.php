<?php

use yii\helpers\Html;
use yii2mod\linkpreview\LinkPreview;

?>

<?php echo Html::textArea("message", '', array('id' => 'contentForm_message', 'class' => 'form-control autosize contentForm', 'rows' => '1', 'placeholder' => Yii::t("PostModule.widgets_views_postForm", "What's on your mind?"))); ?>


<?php echo \humhub\widgets\RichTextEditor::widget(array(
    'id' => 'contentForm_message',
));
?>
<?php
if (getenv('ALLOW_LINK_PREVIEW') == 'true') {
    echo LinkPreview::widget([
        'selector' => '#contentForm_message',
        'clientOptions' => [
            'previewActionUrl' => '/kodeplus_space/link-extract/link-preview',
            'renderOnlyOnce' => false
        ],
    ]);
    echo '<script>
    $(document).ready(function () {
        document.getElementById("contentForm_message_contenteditable").addEventListener("keyup", function (e) {
            $("#contentForm_message").val($("#contentForm_message_contenteditable").text());
            $("#contentForm_message").trigger(
                jQuery.Event("keyup", {keyCode: e.keyCode, which: e.keyCode}));
        });
        document.getElementById("contentForm_message_contenteditable").addEventListener("paste", function (e) {
            $("#contentForm_message").val($("#contentForm_message_contenteditable").text());
            $("#contentForm_message").trigger(
                jQuery.Event("paste"));
        });
        $("#post_submit_button").on("click", function(){
            $(".close-preview-btn").trigger("click");
        });
    });

</script>';
}
?>

