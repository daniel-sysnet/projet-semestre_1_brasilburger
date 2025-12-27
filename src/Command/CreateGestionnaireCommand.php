<?php

namespace App\Command;

use App\Entity\Gestionnaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-gestionnaire',
    description: 'Crée un nouveau gestionnaire.',
)]
class CreateGestionnaireCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nom', InputArgument::REQUIRED, 'Nom du gestionnaire')
            ->addArgument('prenom', InputArgument::REQUIRED, 'Prénom du gestionnaire')
            ->addArgument('telephone', InputArgument::REQUIRED, 'Téléphone')
            ->addArgument('login', InputArgument::REQUIRED, 'Login')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe en clair');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $login = $input->getArgument('login');
        $existing = $this->entityManager->getRepository(Gestionnaire::class)->findOneBy(['login' => $login]);

        if ($existing) {
            $output->writeln('Gestionnaire existe déjà, mise à jour du mot de passe.');
            $gestionnaire = $existing;
        } else {
            $gestionnaire = new Gestionnaire();
            $gestionnaire->setNom($input->getArgument('nom'));
            $gestionnaire->setPrenom($input->getArgument('prenom'));
            $gestionnaire->setTelephone($input->getArgument('telephone'));
            $gestionnaire->setLogin($login);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($gestionnaire, $input->getArgument('password'));
        $gestionnaire->setPassword($hashedPassword);

        $this->entityManager->persist($gestionnaire);
        $this->entityManager->flush();

        $output->writeln('Gestionnaire traité avec succès : ' . $gestionnaire->getLogin());

        return Command::SUCCESS;
    }
}