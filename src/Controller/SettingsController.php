<?php
namespace App\Controller;

use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $settings = $em->getRepository(Setting::class)->find(1);

        return $this->render('settings/index.html.twig', [
            'settings' => $settings,
        ]);
    }

    #[Route('/settings/save', name: 'app_settings_save', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$this->isCsrfTokenValid('settings_company', $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_settings');
        }

        $settings = $em->getRepository(Setting::class)->find(1);

        if (!$settings) {
            $settings = new Setting();
        }

        $settings->setCompanyName($request->request->get('company_name'));
        $settings->setRegNumber($request->request->get('reg_number'));
        $settings->setContactEmail($request->request->get('contact_email'));
        $settings->setContactPhone($request->request->get('contact_phone'));
        $settings->setCity($request->request->get('city'));
        $settings->setWebsite($request->request->get('website'));
        $settings->setAddress($request->request->get('address'));

        $em->persist($settings);
        $em->flush();

        $this->addFlash('success', 'Company information updated successfully.');
        return $this->redirectToRoute('app_settings');
    }
}