# Email development

Responsive emails are made possible with the awesome [Pimcore Emailizr](https://github.com/dachcom-digital/pimcore-emailizr).  
To use its functionality inside a pimcore email, follow these simple steps.

First extend your email template with the layout from Emailizr.

```twig
{% extends '@Emailizr/layout.html.twig' %}
```

Now you can build your email within the following twig block.

```twig
{% block content %}
    {# email contents go here #}
{% endblock %}
```

Please read the [Foundation for Emails documentation](http://foundation.zurb.com/emails/docs/sass-guide.html) carefully!  
By the way, Pimcore editables are fully supported!

Further information can be found [here](https://github.com/dachcom-digital/pimcore-emailizr).
