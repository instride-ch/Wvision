# Tools

## Mailer
Create a controller method and process the data from a symfony form.
The following code sends two emails to client and admin.

**src/AppBundle/Controller/ExampleController.php**
```php
use AppBundle\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @param Request $request
 * 
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function contactFormAction(Request $request)
{
    $success = false;
    
    $form = $this->createForm(ContactFormType::class);
    $form->handleRequest();

    if ($form->isValid()) {
        $data = $form->getData();
        $success = $this->get('WvisionBundle\Tool\Mailer')
            ->sendEmails($data, 'admin@email.com');
    }
    
    return $this->renderTemplate('Contact/contact-form.html.twig', [
        'form' => $form->createView(),
        'success' => $success
    ]);
}
```

## iCalendar
Create a controller method and define a static route for it. Inspect the example below:

**Pimcore static route**
| Name | Pattern | Reverse | Bundle | Controller | Action | Variables | Defaults | Site IDs | Priority |
|------|---------|---------|--------|------------|--------|-----------|----------|----------|----------|
| app_ics | /\/generate-ics\/([0-9]+)/ | /generate-ics/%id | | example | generate-ics | id | | | 1 |

**src/AppBundle/Controller/ExampleController.php**
```php
use Pimcore\File;
use Pimcore\Model\Object;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @return Response The generated iCalendar file
 */
public function generateIcsAction($id)
{
    $event = Object\News::getById($id);
    $filename = File::getValidFilename($event->getTitle());

    $properties = [
        'description' => $event->getDescription(),
        'dtstart' => $event->getDateStart(),
        'dtend' => $event->getDateEnd(),
        'location' => $event->getLocation(),
        'summary' => $event->getSummary(),
        'url' => $event->getWeblink()
    ];

    $ics = $this->get('WvisionBundle\Tool\Ics');
    $ics->setProps($properties);
    $fileContent = $ics->toString();

    $response = new Response($fileContent);
    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        $filename . '.ics'
    );

    $response->headers->set('Content-Type', 'text/calendar');
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
}
```

Now you can generate a iCalendar file with the following twig function:
```twig
{{ pimcore_url({ id: newsObject.getId() }, 'app_ics') }}
```