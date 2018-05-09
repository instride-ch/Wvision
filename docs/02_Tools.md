# Tools

## Mailer
Create a controller method and process the data from a symfony form.
The following code sends two emails to client and admin.

**src/AppBundle/Controller/ExampleController.php**
```php
use AppBundle\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WvisionBundle\Tool\Mailer;

/**
 * @param Request $request
 * @return Response
 */
public function contactFormAction(Request $request)
{    
    $form = $this->createForm(ContactFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        
        $success = $this->get(Mailer::class)->sendEmails($data, ['admin@email.com']);
        
        if ($success) {
            $this->addFlash('success', 'app.form.contact.success');
        } else {
            $this->addFlash('danger', 'app.form.contact.danger');
        }
        
        return $this->redirect($request->getPathInfo());
    }
    
    return $this->renderTemplate('Contact/contact-form.html.twig', [
        'form' => $form->createView()
    ]);
}
```

## iCalendar
Create a controller method and define a static route for it. Inspect the example below:

**Pimcore Static Route**

| Name    | Pattern                    | Reverse           | Controller | Action       | Variables | Priority |
|---------|----------------------------|-------------------|------------|--------------|-----------|----------|
| app_ics | /\/generate-ics\/([0-9]+)/ | /generate-ics/%id | example    | generate-ics | id        | 1        |

**src/AppBundle/Controller/ExampleController.php**
```php
use Pimcore\File;
use Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use WvisionBundle\Tool\Ics;

/**
 * @return Response The generated iCalendar file
 */
public function generateIcsAction($id)
{
    $event = DataObject\News::getById($id);
    $filename = File::getValidFilename($event->getTitle());

    $properties = [
        'description' => $event->getDescription(),
        'dtstart' => $event->getDateStart(),
        'dtend' => $event->getDateEnd(),
        'location' => $event->getLocation(),
        'summary' => $event->getSummary(),
        'url' => $event->getWeblink()
    ];

    $ics = $this->get(Ics::class);
    $ics->setProps($properties);

    $response = new Response($ics->toString());
    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        sprintf('%s.ics', $filename)
    );

    $response->headers->set('Content-Type', 'text/calendar');
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
}
```

Now you can generate a iCalendar file with the following twig function:
```twig
{{ path('app_ics', { id: newsObject.getId() }) }}
```