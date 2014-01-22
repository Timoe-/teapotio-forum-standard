<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    ForumBundle
 * @author     Thomas Potaire
 */

namespace Teapot\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Teapot\Base\ForumBundle\Form\CreateMessageType as BaseCreateMessageType;

class CreateMessageType extends BaseCreateMessageType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $wysiwygClass = 'wysiwyg';

        if ($options['new_entry'] === true) {
            $wysiwygClass .= ' wysiwyg-initial';
        }


        $builder
            ->add('body', 'wysiwyg_textarea', array(
                'label' => false,
                'attr' => array('class' => $wysiwygClass)
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Teapot\ForumBundle\Entity\Message',
            'new_entry'  => false,
        ));
    }
}
