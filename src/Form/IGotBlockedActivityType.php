<?php


namespace App\Form;


use App\Entity\Activity;
use App\Entity\LicensePlate;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class IGotBlockedActivityType extends AbstractType
{

    protected Security $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options['carCount'] == 1) {
            $builder->add('blockee', EntityType::class, [
                'class' => LicensePlate::class,
                'query_builder' => function (EntityRepository $et) {
                    return $et->createQueryBuilder('l')
                        ->andWhere('l.user_id = :var')
                        ->setParameter('var', $this->security->getUser()->getId());
                },
                'choice_label' => 'license_plate',
                'disabled' => true,]);
        } else {
            $builder->add('blockee', EntityType::class, [
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
            //->add('blockee')
            ->add('blocker');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
            'carCount' => 0
        ]);
    }
}