<?php

/*
@ Program   : phpBB CURL Library
@ Author    : Dean Newman, Afterlife(69)
@ Purpose   : Remote login and posting in phpBB
@ Filename  : curl_phpbb.class.php
@ Class     : curl_phpbb
@ About     : Function lib for login, posting and logout on remote phpbb's
@ Licence   : GNU/General Public Licence v2
@ Created   : 6/22/2006, 7:41pm
@ Updated   : 9/01/2006, 5:19pm
@ Updated   : 4/01/2008, 7:39pm
*/

/*
@ Changelog
@   9/01/2006: Added ->read() functionality.
@   4/01/2008: (Betalord) -> modified the script to work with phpbb3 forum. Not all methods were fixed though (only login and post reply)
*/

class curl_phpbb
{
    /*
    @ Variable  : $curl (Resource)
    @ About     : The cURL object used for the request
    @ Type      : Private
    */
    var $curl = null;

    /*
    @ Variable  : $cookie_name (String)
    @ About     : The filename of the temp file used for storing cookies
    @ Type      : Private
    */
    var $cookie_name = array();

    /*
    @ Variable  : $phpbb_url (String)
    @ About     : The address of the remote phpbb that is being connected to
    @ Type      : Private
    */
    var $phpbb_url = null;

    /*
    @ Variable  : $error (Array)
    @ About     : The array including error code and message on errors
    @ Type      : Public
    */
    var $error = array();


    var $user_agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36";

    /*
    @ Function  : curl_phpbb() - Constructor
    @ About     : Check if CURL is available and the url exists.
    @ Type      : Public
    */
    function curl_phpbb($phpbb_url, $cookie_name = 'tmpfile.tmp')
    {
        // Check CURL is present
        if (!function_exists('curl_init')) {
            // Output an error message
            trigger_error('curl_phpbb::error, Sorry but it appears that CURL is not loaded, Please install it to continue.');
            return false;
        }
        if (empty($phpbb_url)) {
            // Output an error message
            trigger_error('curl_phpbb::error, The phpBB location is required to continue, Please edit your script.');
            return false;
        }
        // Set base location
        $this->phpbb_url   = $phpbb_url;
        // Create temp file
        $this->cookie_name = $cookie_name;
    }

    function get_token($forum_id, $topic_id = 0)
    {
        if ($forum_id && $topic_id) {
            $url = 'posting.php?mode=reply&f=' . $forum_id . '&t=' . $topic_id;
        } else {
            $url = 'posting.php?mode=post&f=' . $forum_id;
        }

        $postPage = $this->read($url);
        if (!$postPage) {
          $errormsg = "Unable to retrieve 'post a reply' page";
          return false;
        }

        $token = substr($postPage, strpos($postPage, '<input type="hidden" name="form_token" value="')+46);
        $token = substr($token, 0, strpos($token, '"/>'));

        return $token;
    }

    /*
    @ Function  : login() - Log In
    @ About     : Does a remote login to the target phpBB and stores in cookie
    @ Type      : Public
    */
    function login($username, $password)
    {
        // Generate post string
        $post_fields = $this->array_to_http(array(
            'username' => $username,
            'password' => $password,
            'autologin' => 1,
            'redirect' => 'index.php',
            'login' => 'Login'
        ));
        // Init curl
        $this->curl  = curl_init();
        // Set options
        curl_setopt($this->curl, CURLOPT_URL, $this->phpbb_url . 'ucp.php?mode=login');
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent);
        // Execute request
        $result = curl_exec($this->curl);
        // Error handling
        if (curl_errno($this->curl)) {
            $this->error = array(
                curl_errno($this->curl),
                curl_error($this->curl)
            );
            curl_close($this->curl);
            return false;
        }
        // Close connection
        curl_close($this->curl);
        // Return result
        return true;
    }

    /*
    @ Function  : read() - Read a pages contents
    @ About     : Returns the contents of a url
    @ Type      : Public
    */
    function read($page_url)
    {
        // Init curl
        $this->curl = curl_init();
        // Set options
        curl_setopt($this->curl, CURLOPT_URL, $this->phpbb_url . $page_url);
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent);
        // Execute request
        $result = curl_exec($this->curl);
        // Error handling
        if (curl_errno($this->curl)) {
            $this->error = array(
                curl_errno($this->curl),
                curl_error($this->curl)
            );
            curl_close($this->curl);
            return false;
        }
        // Close connection
        curl_close($this->curl);
        // Return result
        return $result;
    }

    /*
    @ Function  : new_pm() - New PM
    @ About     : Remotely sends a pm to a user of a phpbb forum.
    @ Type      : Public
    */
    function new_pm($username, $message, $topic_title)
    {
        // Generate post string
        $post_fields = $this->array_to_http(array(
            'post' => 'Submit',
            'mode' => 'post',
            'message' => $message,
            'username' => $username,
            'subject' => $topic_title,
            'disable_bbcode' => 0,
            'disable_smilies' => 0,
            'attach_sig' => 1
        ));
        // Location
        $url_vars    = $this->array_to_http(array(
            'mode' => 'post',
            'u' => $username
        ));
        // Init curl
        $this->curl  = curl_init();
        // Set options
        curl_setopt($this->curl, CURLOPT_URL, $this->phpbb_url . 'posting.php?' . $url_vars);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent);
        // Execute request
        $result = curl_exec($this->curl);
        // Get the result
        if (preg_match('#<td align="center"><span class="gen">Your message has been sent.<br \/><br \/>#is', $result, $match)) {
            $post_status = 1;
        } else if (preg_match('#<td align="center"><span class="gen">You cannot make another post so soon after your last; please try again in a short while.<\/span><\/td>>#is', $result, $match)) {
            $post_status = 0;
        } else {
            $post_status = 0;
        }
        // Error handling
        if (curl_errno($this->curl)) {
            $this->error = array(
                curl_errno($this->curl),
                curl_error($this->curl)
            );
            curl_close($this->curl);
            return false;
        }
        // Close connection
        curl_close($this->curl);
        // Return result
        return $post_status;
    }

    /*
    @ Function  : new_topic() - New Topic
    @ About     : Remotely posts a topic to the target phpBB forum.
    @ Type      : Public
    */
    function new_topic($forum_id, $message, $topic_title)
    {
        $token = $this->get_token($forum_id);

        // Generate post string
        $post_fields = $this->array_to_http(array(
            'subject' => $topic_title,
            'addbbcode20' => 100,
            'message' => $message,
            'lastclick' => time(),
            'post' => 'Submit',
            'topictype' => 0,
            'disable_bbcode' => 0,
            'disable_smilies' => 0,
            'disable_magic_url' => 0,
            'attach_sig' => 1,
            'topic_time_limit' => 0,
            'show_panel' => 'options-panel',
            'creation_time' => time(),
            'form_token' => $token,
            'poll_title' => '',
            'poll_option_text' => '',
            'poll_max_options' => 1,
            'poll_length' => 0
        ));
        // Location
        $url_vars    = $this->array_to_http(array(
            'mode' => 'post',
            'f' => $forum_id
        ));
        // Init curl
        $this->curl  = curl_init();
        // Set options
        curl_setopt($this->curl, CURLOPT_URL, $this->phpbb_url . 'posting.php?' . $url_vars);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent);
        // Execute request
        $result = curl_exec($this->curl);
        // Get the result
        $headers = curl_getinfo($this->curl);
        if (strpos($headers['redirect_url'], 'viewtopic.php')) {
            $post_status = 1;
        } else {
            $post_status = 0;
        }
        // if (preg_match('#<td align="center"><span class="gen">Your message has been entered successfully.<br \/><br \/>#is', $result, $match)) {
        //     $post_status = 1;
        // } else if (preg_match('#<td align="center"><span class="gen">You cannot make another post so soon after your last; please try again in a short while.<\/span><\/td>>#is', $result, $match)) {
        //     $post_status = 0;
        // } else {
        //     $post_status = 0;
        // }
        // Error handling
        if (curl_errno($this->curl)) {
            $this->error = array(
                curl_errno($this->curl),
                curl_error($this->curl)
            );
            curl_close($this->curl);
            print_r($this->error);
            return false;
        }
        // Close connection
        curl_close($this->curl);
        // Return result
        return $post_status;
    }

    /*
    @ Function  : topic_reply() - Topic Reply
    @ About     : Remotely replys a post to a topic to the target phpBB forum.
    @ Type      : Public
    */
    function topic_reply($forum_id, $topic_id, $message, $topic_title)
    {
        $token = $this->get_token();

        // Generate post string
        $post_fields = $this->array_to_http(array(
            'subject' => $topic_title,
            'addbbcode20' => 100,
            'message' => $message,
            'topic_cur_post_id' => $topic_id,
            'lastclick' => time(),
            'post' => 'Submit',
            'disable_bbcode' => 0,
            'disable_smilies' => 0,
            'disable_magic_url' => 0,
            'attach_sig' => 1,
            'topic_time_limit' => 0,
            'show_panel' => 'options-panel',
            'creation_time' => time(),
            'form_token' => $token
        ));
        // Location
        $url_vars    = $this->array_to_http(array(
            'mode' => 'reply',
            'f' => $forum_id,
            't' => $topic_id,
        ));
        // Init curl
        $this->curl  = curl_init();
        // Set options
        curl_setopt($this->curl, CURLOPT_URL, $this->phpbb_url . 'posting.php?' . $url_vars);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent);
        // Execute request
        $result = curl_exec($this->curl);
        // Get the result
        $headers = curl_getinfo($this->curl);
        if (strpos($headers['redirect_url'], 'viewtopic.php')) {
            $post_status = 1;
        } else {
            $post_status = 0;
        }
        // if (preg_match('#<td align="center"><span class="gen">Your message has been entered successfully.<br \/><br \/>#is', $result, $match)) {
        //     $post_status = 1;
        // } else if (preg_match('#<td align="center"><span class="gen">You cannot make another post so soon after your last; please try again in a short while.<\/span><\/td>>#is', $result, $match)) {
        //     $post_status = 0;
        // } else {
        //     $post_status = 0;
        // }
        // Error handling
        if (curl_errno($this->curl)) {
            $this->error = array(
                curl_errno($this->curl),
                curl_error($this->curl)
            );
            curl_close($this->curl);
            return false;
        }
        // Close connection
        curl_close($this->curl);
        // Return result
        return $post_status;
    }

    /*
    @ Function  : logout() - Log Out
    @ About     : Logs out of the target phpBB properly.
    @ Type      : Public
    */
    function logout()
    {
        // Generate post string
        $urlopt     = $this->array_to_http(array(
            'logout' => 'true',
            'mode' => 'logout'
        ));
        // Init curl
        $this->curl = curl_init();
        // Set options
        curl_setopt($this->curl, CURLOPT_URL, $this->phpbb_url . 'ucp.php?' . $urlopt);
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_name);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->user_agent);
        // Execute request
        $result = curl_exec($this->curl);
        // Error handling
        if (curl_errno($this->curl)) {
            $this->error = array(
                curl_errno($this->curl),
                curl_error($this->curl)
            );
            curl_close($this->curl);
            return false;
        }
        // Close connection
        curl_close($this->curl);
        // Delete cookie file
        @unlink($this->cookie_name);
        // Return result
        return true;
    }

    /*
    @ Function  : getCurl() - return curl object
    @ About     : Returns curl object
    @ Type      : Public
    */
    function getCurl()
    {
        return $this->curl;
    }

    /*
    @ Function  : array_to_http() - Converter
    @ About     : Converts data from array to http string
    @ Type      : Private
    */
    function array_to_http($array)
    {
        $retvar = '';
        while (list($field, $data) = @each($array)) {
            $retvar .= (empty($retvar)) ? '' : '&';
            $retvar .= urlencode($field) . '=' . urlencode($data);
        }
        return $retvar;
    }

    function array_to_http2($array)
    {
        $retvar = '';
        while (list($field, $data) = @each($array)) {
            $retvar .= (empty($retvar)) ? '' : '&amp;';
            $retvar .= urlencode($field) . '=' . urlencode($data);
        }
        return $retvar;
    }


}

?>
