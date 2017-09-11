# Twig Helpers

### Obfuscate email
> Type: filter

If you need to hide email addresses to bots, simply apply this filter.
```twig
{{ 'example@domain.com'|obfuscateEmail }}
```

### Truncate
> Type: filter

If you want to "cut" a string, you can use this filter.
```twig
{{ truncate(myString, 20) }}
```
This function actually provides even more functionality, but have a [look yourself](../src/Twig/Extension/TruncateExtension.php).

### Url locale
> Type: filter

This filter transforms an ISO formatted locale into a nice url locale (e.g. `de_CH => de-ch`)
```twig
{{ _locale|urlLocale }}
```