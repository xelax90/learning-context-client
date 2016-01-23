# Learning Context Client

This is a PHP implementation for the Learning Context API

## Token Storage
To use this API, you have to implement a token storage which implements the ```LearningContextClient\Storage\StorageInterface```. Storage clases using the ```$_SESSION``` array and Zend Session are already provided in ```LearningContextClient\Storage\SessionArrayStorage``` and ```LearningContextClient\Storage\ZendSessionStorage``` respectively.

## Configuration

To run the Client, you have to create a ```LearningContextClient\Config``` object. It recieves your appId and appSecret, a callback URL which will be called after OAuth authorization and handles the refresh token and an instance of ```LearningContextClient\Storage\StorageInterface```. It suffices to do any request to the API or to call the ```LearningContextClient:::getTokenManager``` function.

## Usage

Create an instance of ```LearningContextClient\Client``` and provide it with your configuration to use the API.

```
$storage = new \LearningContextClient\Storage\SessionArrayStorage();
$config = new \LearningContextClient\Config($APP_ID, $APP_SECRET, $CALLBACK_URL, $storage);
$lc = new \LearningContextClient\Client($config);
```

### Obtain token

To obtain a token, you simple have to call any API interface or you call the ```LearningContextClient::getTokenManager``` function.
