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
{{ 'A string that is too long and has to be shortened.'|truncate(20) }}
```
This function actually provides even more functionality, but [have a look yourself](../src/Twig/Extension/TruncateExtension.php).
