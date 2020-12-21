<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Exception\OAuthServerException as LeagueException;

class LoginController extends AccessTokenController
{
    use ResponseTrait;

    public function issueToken(ServerRequestInterface $request)
    {
        $validatorResponse = $this->validateRequest($request, [
            'grant_type'    => ['required', 'in:password,client_credentials'],
            'client_id'     => 'required',
            'username'      => 'required_if:grant_type,password',
            'password'      => 'required_if:grant_type,password',
            'client_secret' => 'required'
        ]);

        if (!empty($validatorResponse)) {
            return $this->setStatus(FALSE)
                ->setStatusCode(400)
                ->setMessage('validation_error')
                ->sendArray($validatorResponse);
        }

        $data = [];
        try {
            $tokenResponse = parent::issueToken($request);

            $content = $tokenResponse->content();
            $tokenData = json_decode($content, true);
            if (isset($tokenData["error"])) {
                $this->setStatus(false);
                $this->setMessage('invalid_credentials');
                $this->setStatusCode(401);
            } else {
                $body = $request->getParsedBody();
                if(!empty($body['username'])){
                    $email = $body['username'];
                    $user = User::where('email', $email)
                        ->where('is_enabled', 1)
                        ->first();

                    foreach ($user->tokens as $key => $token) {
                        if ($key > 0) {
                            $token->delete();
                        }
                    }
                }

                $data = $tokenData;
                $this->setStatus(true);
                $this->setStatusCode(200);
            }
        } catch (LeagueException $e) {
            $payload = $e->getPayload();
            $this->setStatus(false);
            $this->setStatusCode(500);
            $this->setMessage($payload['message']);
        }

        return $this->sendArray($data);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->token()->revoke();
        $user->token()->delete();

        $this->setStatus(true);
        $this->setStatusCode(200);
        $this->setMessage('logged_out');

        return $this->send();
    }

    protected function validateRequest($request, $rules)
    {
        //Perform Validation
        $validator = \Validator::make(
            $request->getParsedBody(),
            $rules
        );
        return $this->getValidationErrors($validator);
    }

    public function getValidationErrors($validator)
    {
        $result = [];
        if ($validator->fails()) {
            $errorTypes = $validator->failed();
            // crete error message by using key and value
            foreach ($errorTypes as $key => $value) {
                $result[$key] = strtolower(array_keys($value)[0]);
            }
        }

        return $result;
    }
}
