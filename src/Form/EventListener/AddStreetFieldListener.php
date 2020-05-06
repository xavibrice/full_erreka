<?php


namespace App\Form\EventListener;


use App\Entity\Street;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AddStreetFieldListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    private function addStreetForm($form, $zone_id)
    {
        $formOptions = [
            'label' => 'Calle',
            'class' => Street::class,
            'query_builder' => function(EntityRepository $repository) use ($zone_id)
            {
                return $repository->createQueryBuilder('s')
                    ->innerJoin('s.zone', 'z')
                    ->where('z.id = :zone')
                    ->setParameter('zone', $zone_id)
                ;
            },
            'placeholder' => 'Selecciona calle',
        ];
        $form->add('street', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        if (null === $data) {
            return;
        }
        $accessor = PropertyAccess::createPropertyAccessor();
        $street = $accessor->getValue($data, 'street');
        $zone_id = ($street) ? $street->getZone()->getId() : null;
        $this->addStreetForm($form, $zone_id);
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        $zone_id = array_key_exists('zone', $data) ? $data['zone'] : null;
        $this->addStreetForm($form, $zone_id);
    }
}