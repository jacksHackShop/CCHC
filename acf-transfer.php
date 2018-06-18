<?php
require_once 'class-mysql_handler.php';

/*  Takes acf image gallery and adds the images to a repeater field
    $post_ids: the posts to which the meta fields belong
    $gallery_name: the name of the acf image galleries
    $repeater_name: The name of the acf repeater you want to add images to
    $image_row_name: the name of the acf repeater field you want the image to go in
*/ 
function importGalleryImageToRepeater($post_ids, $gallery_name, $repeater_name, $image_row_name){
    // get mysql connection
    $user = '<REMOVED FOR SECURITY>';
    $pw = '<REMOVED FOR SECURITY>';
    $host = '<REMOVED FOR SECURITY>';
    $db_name = '<REMOVED FOR SECURITY>';
    $mysql = new MysqlHandler($user, $pw, $host, $db_name);


    foreach ($post_ids as $post_id) {

        // get current row number TODO add condition for post id
        $result = $mysql->execute(
            'SELECT meta_value FROM wp_postmeta WHERE post_id = :post_id AND meta_key= :repeater_name', 
            [':post_id'=>$post_id, ':repeater_name'=>$repeater_name], 
            true); 
        $cur_row = $result[0]['meta_value'];
        
        $image_gallery = $mysql->execute(
            'SELECT meta_value FROM wp_postmeta WHERE post_id = :post_id AND meta_key = :meta_key',
            [':post_id'=>$post_id, 'meta_key'=>$gallery_name],
            true);
        // parse results for image ids
        preg_match_all ('/"([^;]*)"/', $image_gallery[0]['meta_value'], $match);
        $image_ids = $match[1];

        $repeater_info = $mysql->select('wp_posts', ['post_name', 'ID'], [['column'=> 'post_excerpt', 'operator'=>'=', 'value'=>$repeater_name]])[0];

        $repeater_fields_info = $mysql->select('wp_posts', ['ID', 'post_name', 'post_excerpt'], [['column'=>'post_parent', 'operator'=>'=', 'value'=>$repeater_info['ID']]] );
        
        // add all the rows
        foreach ($image_ids as $image_id) {
            foreach ($repeater_fields_info as $subfield) {
                $current_row_name = $repeater_name.'_'.$cur_row.'_'.$subfield['post_excerpt'];

                $mysql->insert('wp_postmeta', 
                        ['post_id'=>$post_id, 
                        'meta_key'=>"_".$current_row_name, 
                        'meta_value'=>$subfield['post_name']]);

                if ($subfield['post_excerpt'] == $image_row_name) {
                    $mysql->insert('wp_postmeta', 
                        ['post_id'=>$post_id, 
                        'meta_key'=>$current_row_name, 
                        'meta_value'=>$image_id]);
                    
                } else {
                    $mysql->insert('wp_postmeta', 
                        ['post_id'=>$post_id, 
                        'meta_key'=>$current_row_name, 
                        'meta_value'=>'']);
                }
                
            }
            $cur_row++;
        }
        
    }
    // add the number of new rows to the $repeater meta_value
    $mysql->update('wp_postmeta', 
        ['meta_value'=>$cur_row], 
        [['column'=>'meta_key', 'operator'=>'=', 'value'=>$repeater_name]]);
}

//importGalleryImageToRepeater([83, 71, 72, 73, 56, 9, 678], 'image_gallery', 'slide_gallery', 'image');