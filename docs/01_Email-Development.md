# Email development

A full-featured workflow for responsive emails comes with this bundle.  
To use its functionality inside a pimcore email, simply add the bundle in your AppKernel.php
and extend the base layout.

```php
$collection->addBundle(new \Hampe\Bundle\ZurbInkBundle\HampeZurbInkBundle());
```

```twig
{% extends 'HampeZurbInkBundle:FoundationForEmails:2/base.html.twig' %}
```

Usually this is done inside your own email layout.  
Then the only thing left is that you have to include a link to a css file.

```twig
{% block preHtml %}
    {# add your css files here, please use a bundle relative path #}
    {{ zurb_ink_styles.add('@WvisionBundle/Resources/public/static/css/mail.css') }}
{% endblock %}
```

Now you can build your email within the following twig block.  
The full documentation on what you can use can be found [here](http://foundation.zurb.com/emails/docs/sass-guide.html) (read carefully)!

```twig
{% block content %}
    {# email contents go here #}
{% endblock %}
```