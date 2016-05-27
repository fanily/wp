<?php

/*
Plugin Name: Tagged User Notification
Plugin URI: http://www.brokencitylab.org
Description: Send a notification to user tagged in a comment
Version: 0.2
Author: Justin Langlois, based on code from Fabio Trezzi's Notify on Comment plugin
Author URI: 
*/

function notifyOnUserTag_send($commentID){
	global $wpdb;
	
	// Get the content of the comment
	$comment = get_comment($commentID);

	// look in the comment content
	$content = $comment->comment_content;
	
	$matches = array();
	//find the @user text
	preg_match_all("/\@[a-z0-9_]+/i", $content, $matches, PREG_SET_ORDER);
	
	//examples of how this is found, notice the structure of the array
	//$firstuser = $matches[0][0];
	//$seconduser = $matches[1][0];
	//$thirduser = $matches[2][0];
	
	$taggeduser = array();
	//preg_match("/\@[a-z0-9_]+/i", $content, $matches);
	$useremails = array();
	$c = count($matches);
	$i = 0;	
	
	while ($i < $c) {
	
		$taggeduser[$i] = $matches[$i][0];
	
		//get just the user name, remove the @ symbol
		$taggeduser[$i] = str_replace('@', '', $taggeduser[$i]);		
								
				// prepare arguments
					$args  = array(
						'meta_query' => array(
						    array(
						        // uses compare like WP_Query
						        'key' => 'nickname',
						        'value' => $taggeduser[$i],
						        'compare' => '='
						        ),
						));
						
						// Create the WP_User_Query object
						$wp_user_query = new WP_User_Query($args);
						// Get the results
						$authors = $wp_user_query->get_results();
					
						    // loop trough each author
						    foreach ($authors as $author)
						    {
						        // get all the user's data
						        $author_info = get_userdata($author->ID);
						        $useremails[$i] = $author_info->user_email;
						         
						    }
						    
						$i++;
			}
		//put all emails in a comma separated list	
		$allemails = implode(', ', $useremails);
		//put all names in a comma separated list
		$allnames = implode(', ', $taggeduser);
		//add the word "and" rather than a comma between the last and second to last names
		$allnames = preg_replace('#,(?![^,]+,)#',' and',$allnames);

						
//}
	$post = get_post($comment->comment_post_ID);
	$user = get_userdata($post->post_author);
	$to = $allemails;
	
	// Set the send from
	$admin_email = get_option('admin_email');
	$headers= "From:$admin_email\r\n";
	$headers .= "Reply-To:$admin_email\r\n";
	$headers .= "X-Mailer: PHP/".phpversion();
	$template = get_option('notifyOnUserTag_emailTemplate');
	
	
	// If not setted load the default from file
	$template = file_get_contents(dirname(__FILE__) . '/defaultTemplate.php');

	// Replace all the constant with the rights values
	$template = str_replace("{postID}", $post->ID, $template);
	$template = str_replace("{postTitle}", $post->post_title, $template);
	$template = str_replace("{author}", $comment->comment_author, $template);
	$template = str_replace("{authorIp}", $comment->comment_author_IP, $template);
	$template = str_replace("{authorDomain}", $comment_author_domain, $template);
	$template = str_replace("{authorEmail}", $comment->comment_author_email, $template);
	$template = str_replace("{authorUrl}", $comment->comment_author_url, $template);
	$template = str_replace("{commentContent}", $comment->comment_content, $template);
	$template = str_replace("{commentLink}", get_permalink($comment->comment_post_ID), $template);
	$template = str_replace("{commentID}", $commentID, $template);
	$template = str_replace("{siteUrl}", get_option('siteurl'), $template);
	$template = str_replace("{taggedUser}", $allnames, $template);

	$subject = sprintf( __('You were tagged on: "%2$s"'), get_option('blogname'), $post->post_title );

	@wp_mail($to, $subject, $template, $headers);

	return true;


}

/* if this needs a menu one day...
function notifyOnUserTag_menu() {
	$path = dirname(__FILE__);
	$pathElements = explode('/', $path);
	add_options_page('notifyOnUserTag options', 'notifyOnUserTag', 'manage_options', $pathElements[count($pathElements) - 1] . '/options.php');
}
*/
	


add_action('comment_post', 'notifyOnUserTag_send');
//add_action('admin_menu', 'notifyOnUserTag_menu');

?>