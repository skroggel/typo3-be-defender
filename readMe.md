# be_defender

## Features
This extension adds a simple two-factor authentication via email to the backend login.
For this purpose, an additional mandatory field for a one-time login-code is added to the backend login screen.
This code can be requested by e-mail after entering the account name.
The e-mail is sent to the e-mail address registered with the backend user's account.

**If the environment variable is set to "Development", the generated code will always be "12345".**

Therefore, it must be ensured that the environment variable is set correctly on the production-environment and that the e-mail dispatch is configured correctly.
