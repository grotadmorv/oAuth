# oAuth


__First step: GET auth_token__
Create the auth_token then send it

__Second step: GET form__
Display the form if the auth_token is send

__Third step: POST auth_token__
If the login and the password are ok, then send the form, erase the auth_token and create the confirm_token for send

__Fourth step: GET confirm_token__
Wait for the confirm_token, then generate and return the access_token

__Fifth step: GET secret__
Wait for the request of access_token, then return the allowed access request (secret link user)
