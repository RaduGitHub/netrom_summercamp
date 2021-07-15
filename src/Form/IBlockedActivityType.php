<?php


namespace App\Form;


use App\Entity\Activity;
use App\Entity\LicensePlate;
use App\Entity\User;
use App\Repository\LicensePlateRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class IBlockedActivityType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['carCount'] == 1) {
            $builder->add('blocker', EntityType::class, [
                'class' => LicensePlate::class,
                'query_builder' => function (EntityRepository $et) {
                    return $et->createQueryBuilder('l')
                        ->andWhere('l.user_id = :var')
                        ->setParameter('var', $this->security->getUser()->getId());
                },
                'choice_label' => 'license_plate',
                'disabled' => true,
            ]);
        } else {
            $builder->add('blocker', EntityType::class, [
                'class' => LicensePlate::class,
                'query_builder' => function (EntityRepository $et) {
                    return $et->createQueryBuilder('l')
                        ->andWhere('l.user_id = :var')
                        ->setParameter('var', $this->security->getUser()->getId());
                },
                'choice_label' => 'license_plate',
            ]);
        }

        $builder
//            ->add('Email')
//            ->add('Password')
//                ->setAction($this->generateUrl('handler_search'))
            //->add('blocker')
            ->add('blockee')
            ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
            'carCount' => 0,
        ]);
    }
}