<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"><img src="https://res.cloudinary.com/programathor/image/upload/c_fit,h_200,w_200/v1577378201/g6f91cmmtewwl8wqtzzw.png" width="150"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>


## Desafio Adopets
Foi realizado a criação de uma API <br>
Para emular o projeto, basta seguir os requisitos básico do Laravel 7.x, que se encontra no link: https://laravel.com/docs/7.x

Após isso, utilizar os comandos<br>
"php artisan migrate"<br>
"php artisan passport:install --uuids --force" => yes<br>
"php artisan serve"<br>

#### Dependências do Projeto
```
"php": "^7.2.5",
"fideloper/proxy": "^4.2",
"fruitcake/laravel-cors": "^1.0",
"guzzlehttp/guzzle": "^6.3",
"laravel/framework": "^7.0",
"laravel/passport": "^9.3",
"laravel/tinker": "^2.0",
"nesbot/carbon": "^2.36",
"spatie/laravel-activitylog": "^3.14",
"webpatser/laravel-uuid": "^3.0"
```

```
+--------+----------+---------------------------+----------------+-----------------------------------------------------+------------+
| Domain | Method   | URI                       | Name           | Action                                              | Middleware |
+--------+----------+---------------------------+----------------+-----------------------------------------------------+------------+
|        | GET|HEAD | /                         |                | Illuminate\Routing\ViewController                   | web        |
|        | POST     | api/login                 |                | App\Http\Controllers\UserController@login           | api        |
|        | POST     | api/logout                |                | App\Http\Controllers\UserController@logout          | api        |
|        |          |                           |                |                                                     | auth:api   |
|        | POST     | api/product/find          | product.find   | App\Http\Controllers\ProductController@findProducts | api        |
|        |          |                           |                |                                                     | auth:api   |
|        | POST     | api/product/store         | product.store  | App\Http\Controllers\ProductController@store        | api        |
|        |          |                           |                |                                                     | auth:api   |
|        | DELETE   | api/product/{uuid}/delete | product.delete | App\Http\Controllers\ProductController@delete       | api        |
|        |          |                           |                |                                                     | auth:api   |
|        | POST     | api/product/{uuid}/update | product.update | App\Http\Controllers\ProductController@update       | api        |
|        |          |                           |                |                                                     | auth:api   |
|        | POST     | api/register              | user.store     | App\Http\Controllers\UserController@store           | api        |
|        | GET|HEAD | api/user/me               |                | App\Http\Controllers\UserController@me              | api        |
|        |          |                           |                |                                                     | auth:api   |
+--------+----------+---------------------------+----------------+-----------------------------------------------------+------------+

```

#### api/login 
```
Rota do tipo POST, retorna o token para realizar as requisições que necessitam autenticação 
Exemplo do Response abaixo
```
<img src="https://i.imgur.com/5VXrtEY.png">
<br>

#### api/logout 
```
Rota do tipo POST, invalida o token
Exemplo do Response abaixo
```
<img src="https://i.imgur.com/X9BVfPZ.png">
<br>

#### api/product/find 
```
Rota do tipo POST, que busca todos os produtos que um usuário cadastrou
Para filtrar a busca deve passar como query path a variavel 'q'.
Exemplo: api/product/find?q=capa
Para navegar pela paginação e quantidade de produto, 
deve utilizar como query path as variaveis 'page' e 'per_page' respectivamente
Exemplo do Response abaixo
```
<img src="https://i.imgur.com/gpfzfyd.png">
<br>



#### api/product/store 
```
Rota do tipo POST, armazena o produto e o retorna com sua identificação.
Exemplo do Response abaixo
```
<img src="https://i.imgur.com/IagQ2LA.png">
<br>

#### api/product/{uuid}/delete 
```
Rota do tipo DELETE, exclui o produto especificado na url e retorna uma mensagem da ação.
Exemplo do Response abaixo
```
<img src="https://i.imgur.com/fZSFJhB.png">
<br>


#### api/product/{uuid}/update
```
Rota do tipo POST, atualiza o produto especificado na url e retorna uma mensagem da ação.
Exemplo do Response abaixo
```
<img src="https://i.imgur.com/hEZ90oi.png">
<br>


#### api/register
```
Rota do tipo POST, armazena um usuário e retorna um token para autenticação.
Exemplo do Response abaixo
```
<img src="https://i.imgur.com/VYkXjZQ.png">
<br>


#### api/user/me
```
Rota do tipo GET, retorna as informações de cadastro do usuário.
Exemplo do Response abaixo
```
<img src="https://i.imgur.com/0fKl5c2.png">
<br><br>

#### Test Driven Development
```
Comando para o TDD no laravel
"php artisan test"
Exemplo do Test executado
```

<img src="https://i.imgur.com/sLi9a7y.png">

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
