# RESTful Webservices in Symfony

## Coding Challenge 2 - Serialization

### Tasks

- use the Serializer Component to transform Workshops and Attendees into a JSON string
- use Symfony's `JsonResponse` to return the generated JSON
    
### Solution

- require the serializer component: `composer require serializer`
- inject the Serializer into your Controllers
- serialize your Entity
- create a custom `Normalizer` (implement `ContextAwareNormalizerInterface`) for your Workshop and Attendee Entity
- remove the `toArray` method in your Entity
- add an attendee to one of the workshops and use the PostMan Collection to test the endpoints
- make sure that all json property names are formatted in snake_case
- fix the `CircularReferenceException`

#### Problem 1: No snake_case property names anymore

```yaml
# config/packages/framework.yaml

framework:
    # ...
    serializer:
        name_converter: 'serializer.name_converter.camel_case_to_snake_case'
```

#### Problem 2: Adding an attendee to a workshop and accessing this attendee, or the respective workshop results in a CircularReferenceException

```php
// AttendeeNormalizer

$defaultContext = [
    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => fn ($object, $format, $context) => $object->getFirstname().' '.$object->getLastname(),
];
```

```php
// WorkshopNormalizer

$customContext = [
    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
        if ($object instanceof Attendee) {
            return $object->getFirstname().' '.$object->getLastname();
        }

        if ($object instanceof Workshop) {
            return $object->getTitle();
        }

        return $object;
    },
];
```
