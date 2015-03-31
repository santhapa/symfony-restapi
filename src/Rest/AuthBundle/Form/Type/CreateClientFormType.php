<?php

namespace Rest\AuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateClientFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('redirect_uri', 'text', array(
                'label' => 'Redirect Uri\'s:',
                'required' => false))
                ->add('grant_type', 'choice', array(
                    'choices'   => array('authorization_code' => 'Authorization Code',
                        'token' => 'Implicit',
                        'password' => 'User Credentials',
                        'client_credentials' => 'Client Credentials',
                        'refresh_token' => 'Refresh Token'),
                    'multiple'  => true,
                    'expanded' => true,
                    'required'  => false))
            ;
    }

    public function getName()
    {
        return 'create_client_form';
    }

}