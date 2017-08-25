# Configration

Find below the available options to configure the bundle properly.

| Name        | Type   | Default | Description                                                                               |
|-------------|--------|---------|-------------------------------------------------------------------------------------------|
| folder      | string | -       | Defines the path to a pimcore object folder, which contains all newsletter objects.       |
| smtp        | array  | []      | This array contains all the necessary details for connection with a smtp server.          |
| host        | string | -       | The host of the smtp connection.                                                          |
| security    | string | null    | Defines whether the connection has security or not. Valid options: tls, ssl               |
| port        | int    | 25      | The port of the smtp connection.                                                          |
| name        | string | -       | The name of the smtp connection.                                                          |
| auth_method | string | -       | Defines the authentication method of the connection. Valid option: login, plain, cram-md5 |
| user        | string | -       | The username is needed for a successful authentication.                                   |
| password    | string | -       | The password is needed for a successful authentication.                                   |
| sites       | array  | []      | This array enables smtp-settings for multi-site pimcore instances.                        |
| main_domain | string | -       | The main domain of a pimcore site (is found in site settings).                            |

The config tree will eventually look something like this:

```yaml
wvision:
  newsletter:
  
    # If no site is found, the default stays as fallback.
    default:
      folder: '/newsletter/subscriber'
      smtp:
        host: 'smtp.mailer.com'
        name: 'smtp.mailer.com'
        auth_method: 'login'
        user: 'default'
        password: 'suchS3cur3P@ssw0rd'
        
    sites:
      -
        main_domain: 'domain1.com'
        folder: '/domain1/newsletter/subscriber'
        smtp:
          host: 'smtp.mailer.com'
          name: 'smtp.mailer.com'
          auth_method: 'login'
          user: 'domain1'
          password: 'myS3cur3P@ssw0rd'
      -
        main_domain: 'domain2.com'
        folder: '/domain2/newsletter/subscriber'
        smtp:
          host: 'smtp.mailer.com'
          name: 'smtp.mailer.com'
          auth_method: 'login'
          user: 'domain2'
          password: '@noth3rS3cur3P@ssw0rd'
```