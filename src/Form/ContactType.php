<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject' , TextType::class,[

                'constraints' => [
                new NotBlank([
                        'message' => 'Subject is required'
                    ]),
                    ]
                    ])
            ->add('email', EmailType::class,[
                'constraints' => [
                    new NotBlank([
                            'message' => 'Subject is required'
                        ]),
                    new Email([
                        'message' => 'The email {{ value }} is not a valid email.',
                    ])
                        ]
            ])
            ->add('message', TextType::class,[

                'constraints' => [
                new NotBlank([
                        'message' => 'Message is required'
                    ]),
                    ]
                    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
