
<?php
require_once './vendor/autoload.php';

$oauth = new VK\OAuth\VKOAuth;
$client_id = 7103777; // ID приложения
$client_secret = 'JDq7F9uvX4cwa3xA7FDS'; // Защищённый ключ
$redirect_uri = 'http://localhost:8080'; // Адрес сайта
$scope = [VK\OAuth\Scopes\VKOAuthUserScope::WALL, VK\OAuth\Scopes\VKOAuthUserScope::GROUPS, \VK\OAuth\Scopes\VKOAuthUserScope::FRIENDS];
$display = VK\OAuth\VKOAuthDisplay::PAGE;
$browser_url = $oauth->getAuthorizeUrl(VK\OAuth\VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope);




if(isset($_GET['code'])) {

    $code = $_GET['code'];
    $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
    $access_token = $response['access_token'];

    $vk = new VK\Client\VKApiClient();
    $response  =  $vk ->users()->get($access_token ,['fields'=>['city','photo_200_orig','screen_name']]);
    foreach ($response as $input)
    {
        echo 'Информация о пользователе';
        echo '<p><img src='.$input['photo_200_orig'].' alt='.$input['first_name'].$input['last_name'].'></p>';
        echo '<p><a href="http://vk.com/'.$input['screen_name'].'" >'.$input['first_name'].' '.$input['last_name'].'</a></p>';
        echo '<p>'.$input['city']['title'].'</p>';
    }

    $response = $vk->friends()->get($access_token,['fields'=>['first_name','photo_200_orig','domain','city'],'count'=>'5']);
    echo 'Друзья';
    foreach($response['items'] as $input) {
        echo '<p> <img src='.$input['photo_200_orig'].' alt='.$input['first_name'].$input['last_name'].'></p>';
        echo '<p><a href="http://vk.com/'.$input['domain'].'" >'.$input['first_name'].' '.$input['last_name'].'</a></p>';
        echo '<p>'.$input['city']['title'].'</p>';
    }

}
echo '<p><a href= '. $browser_url .' >Аутентификация через ВКонтакте</a></p>';
?>




