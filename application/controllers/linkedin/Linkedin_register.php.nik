<?php
    session_start();

    $config['base_url']             =   'http://agendatool.servernepal.org/linkedin/auth.php';
    $config['callback_url']         =   'http://agendatool.servernepal.org/linkedin/linkedin_register';
    $config['linkedin_access']      =   '770m4n9dz0ds1c';
    $config['linkedin_secret']      =   '2udRy144J7ZU4AW0';

    include_once "linkedin.php";
   
   
    
    # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
    //$linkedin->debug = true;

   if (isset($_REQUEST['oauth_verifier'])){
        $_SESSION['oauth_verifier']     = $_REQUEST['oauth_verifier'];

       
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->getAccessToken($_REQUEST['oauth_verifier']);
        $_SESSION['oauth_access_token'] = serialize($linkedin->access_token);

       
       /* $this->session->['oauth_verifier'] = $_REQUEST['oauth_verifier'];
        $linkedin->request_token = unserialize($this->session->userdata('requestToken'));
        $linkedin->oauth_verifier = $this->session->userdata('oauth_verifier');
        $linkedin->getAccessToken($_REQUEST['oauth_verifier']);
        $this->session->set_userdata('oauth_access_token') = serialize($linkedin->access_token);*/
        header("Location: " . $config['callback_url']);
        exit;
   }
   else{
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
       /* $linkedin->request_token = unserialize($this->session->userdata('requestToken'));
        $linkedin->oauth_verifier = $this->session->userdata('oauth_verifier');
        $linkedin->access_token = unserialize($this->session->userdata('oauth_access_token'));*/
   }


    # You now have a $linkedin->access_token and can make calls on behalf of the current member
    $xml_response = $linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url,email-address,phone-numbers,location)");

    echo '<pre>';
    echo 'My Profile Info';
    print_r($xml_response);
    echo '<br />';
    echo '</pre>';
?>