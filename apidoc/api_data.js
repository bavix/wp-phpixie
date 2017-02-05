define({ "api": [
  {
    "type": "post",
    "url": "/auth/resource",
    "title": "Test Request",
    "name": "Test",
    "group": "Auth",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "optional": false,
            "field": "Authorization:",
            "description": "<p>Basic access_token</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "bundles/api/src/Project/Api/HTTPProcessors/Auth.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/auth/token",
    "title": "Get Token",
    "name": "Token",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "optional": false,
            "field": "grant_type",
            "defaultValue": "password|client_credentials",
            "description": ""
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "username",
            "defaultValue": "LOGIN",
            "description": ""
          },
          {
            "group": "Parameter",
            "optional": false,
            "field": "password",
            "defaultValue": "PASSWORD",
            "description": ""
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "optional": false,
            "field": "Authorization:",
            "description": "<p>Basic access_token</p> <p>http -a testC:testS -f POST wbs-cms/api/auth/token grant_type=password username=$USER$ password=$PASSWORD$</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "bundles/api/src/Project/Api/HTTPProcessors/Auth.php",
    "groupTitle": "Auth"
  }
] });
