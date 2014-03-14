PeredajJQueryBundle
===================

**PeredajJQueryBundle** - yet another Symfony2 bundle for using jQuery and jQuery plugins.

Installation
------------

1. Download bandle via composer::

    "require": {
        ...
        "peredaj-jquerybundle": "dev-master"
    }


2. For copy library in `@peredajJQueryBundle/Resouces/public` run console command:

    php app/console peredaj:install
    
... and install assets:

    php app/console assets:install
    

Usage
-----

Put in your base twig template in head:

    <link href="{{ asset('peredajjquery/select2/css/select2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('peredajjquery/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>

and at end of page:

    <script src="{{ asset('peredajjquery/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('peredajjquery/select2.js') }}"></script>


Now you can use select2 fiels in your form:

    <?php
    
    public function someAction()
    {
        $form = $this->createFormBuilder()
            ->add('some', 'jquery_select2_choice', array(
                'choices' => array(
                    'one',
                    'two',
                    '...',
                )
            ))
            ->getForm();
            
        return array(
            'form' => $form,
        );
    }
    
and in your view:

    {% extends '::base.html.twig' %}
    
    {% block body %}
    {{ form(form) }}
    {% endblock body %}
    

_compatible with **PeredajBotstrapBundle**_
