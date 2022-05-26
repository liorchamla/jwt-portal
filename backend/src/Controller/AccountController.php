<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/accounts')]
class AccountController extends AbstractController
{
    #[Route('/login', name: 'app_account_login', methods: ['POST'])]
    public function authenticate(Request $request, AccountRepository $repository, UserPasswordHasherInterface $hasher, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        $constraint = new Collection([
            "email" => [new NotBlank(), new Email()],
            "password" => [new NotBlank()]
        ]);

        $errors = $validator->validate($data, $constraint);

        if ($errors->count() > 0) {
            $messages = [];

            foreach ($errors as $error) {
                $messages[] = ['property' => str_replace(['[', ']'], ['', ''], $error->getPropertyPath()), 'message' => $error->getMessage()];
            }

            return $this->json($messages, 400);
        }

        $account = $repository->findOneBy(['email' => $data['email']]);

        if (!$account) {
            return new JsonResponse(['error' => 'Bad credentials'], 401);
        }

        $isPasswordValid = $hasher->isPasswordValid($account, $data['password']);

        if (!$isPasswordValid) {
            return new JsonResponse(['error' => 'Bad credentials'], 401);
        }

        $key = 'example_key';
        $payload = [
            'username' => $account->getEmail(),
            'iat' => time(),
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        return $this->json(['token' => $jwt]);
    }

    #[Route('/', name: 'app_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'accounts' => $accountRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AccountRepository $accountRepository, UserPasswordHasherInterface $hasher): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $account->setPassword($hasher->hashPassword($account, $account->getPassword()));

            $accountRepository->add($account, true);

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Account $account, AccountRepository $accountRepository): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountRepository->add($account, true);

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('account/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, Account $account, AccountRepository $accountRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $account->getId(), $request->request->get('_token'))) {
            $accountRepository->remove($account, true);
        }

        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
