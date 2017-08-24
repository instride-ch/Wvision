#w-vision Pimcore Bundle

### Generate an iCalendar File
Create a controller method and create a route for it. Inspect the example below:

**src/AppBundle/Resources/config/pimcore/routing.yml**
```yaml
generate_ics:
  path: /generate-ics/{id}
  defaults: { _controller: AppBundle:Example:generateIcs }
```

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
        'description' => $event->getShortText(),
        'dtstart' => $event->getDate(),
        'dtend' => $event->getDate(),
        'summary' => $event->getTitle(),
        'url' => 'https://google.ch/'
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