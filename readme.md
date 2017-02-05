```bash
find -type d | xargs chmod 0775
find -type f | xargs chmod 0664
```

```bash
npm install babel-preset-latest --save-dev
npm install babel-preset-react --save-dev
```

[dataTables](https://www.npmjs.com/package/@trungdq88/react-datatable)

$CLIENT_ID в константы
$CLIENT_SECRET в константы

https://wheelpro.ru/api/ -- в константы

```bash
echo 'авторизовать приложение для доступа к данным'
curl -u $CLIENT_ID:$CLIENT_SECRET https://wheelpro.ru/api/auth/token -d "grant_type=client_credentials"

echo 'авторизовать пользователя'
curl -u $CLIENT_ID:$CLIENT_SECRET https://wheelpro.ru/api/auth/token -d "grant_type=password" -d "username=$USERNAME" -d "password=$PASSWORD"

echo 'проверка'
curl --header "Authorization: Bearer $ACCESS_TOKEN" https://wheelpro.ru/api/auth/resource -d "test=test"
```