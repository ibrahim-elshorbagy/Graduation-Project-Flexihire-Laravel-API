# Laravel API

## Login Routes

| Type  | URL                                  | Accepts                                             |
|-------|--------------------------------------|-----------------------------------------------------|
| POST  | `/api/register`                      | `name`, `email`, `password`, `password_confirmation`|
| POST  | `/api/login`                         | `email`, `password`                                 |
| POST  | `/api/logout`                        | `Add the Token`                                     |
| POST  | `/api/forgot-password`               | `email`                                             |

 




# Profile

| Method     | URL                                    | Description                                            | Accepts                                             |
|------------|----------------------------------------|--------------------------------------------------------|-----------------------------------------------------|
| POST       | `/api/profile/update-name`   | Update User Names                                         |`name`|
| POST       | `/api/profile/update-password`| Update User Password                                   |`old_password`, `password`, `password_confirmation`|

       


## Freamworks Used
- Laravel 11
