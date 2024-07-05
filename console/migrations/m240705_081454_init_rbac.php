<?php

use yii\db\Migration;

/**
 * Class m240705_081454_init_rbac
 */
class m240705_081454_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $manageTestimonials = $auth->createPermission('manageTestimonials');
        $manageTestimonials->description = 'Manage all testimonials (full access) ';
        $auth->add($manageTestimonials);

        $manageProjects = $auth->createPermission('manageProjects');
        $manageProjects->description = 'Manage all projects (full access)';
        $auth->add($manageProjects);

        $manageBlogs = $auth->createPermission('manageBlogs');
        $manageBlogs->description = 'Manage all blogs (full access)';
        $auth->add($manageBlogs);

        $viewProjects = $auth->createPermission('viewProject');
        $viewProjects->description = 'Manage all projects (full access)';
        $auth->add($viewProjects);

        $testimonialManager = $auth->createRole('testimonialManager');
        $auth->add($testimonialManager);

        // Testimonial Manager can manage testimonials
        $auth->addChild($testimonialManager, $manageTestimonials);
        $auth->addChild($testimonialManager, $viewProjects);


        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manageTestimonials);
        $auth->addChild($admin, $manageProjects);
        $auth->addChild($admin, $manageBlogs);

        // Assign admin role to user with ID 1
        $auth->assign($admin, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}
