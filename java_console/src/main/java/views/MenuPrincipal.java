package views;

import service.*;
import entity.*;
import java.util.Scanner;
import java.util.List;

public class MenuPrincipal {
    private Scanner scanner = new Scanner(System.in);
    private BurgerService burgerService = new BurgerService();
    private ComplementService complementService = new ComplementService();
    private MenuService menuService = new MenuService();

    public void afficherMenu() {
        System.out.println("=== Restaurant Brasil Burger - Gestion ===");
        System.out.println("1. Gérer les Burgers");
        System.out.println("2. Gérer les Menus");
        System.out.println("3. Gérer les Compléments");
        System.out.println("4. Quitter");
        System.out.print("Choix : ");
    }

    public void gererBurgers() {
        while (true) {
            System.out.println("\n=== Gestion des Burgers ===");
            System.out.println("1. Ajouter un Burger");
            System.out.println("2. Modifier un Burger");
            System.out.println("3. Archiver un Burger");
            System.out.println("4. Lister les Burgers");
            System.out.println("5. Retour");
            System.out.print("Choix : ");
            int choix = scanner.nextInt();
            scanner.nextLine(); // consommer la ligne

            switch (choix) {
                case 1:
                    ajouterBurger();
                    break;
                case 2:
                    modifierBurger();
                    break;
                case 3:
                    archiverBurger();
                    break;
                case 4:
                    listerBurgers();
                    break;
                case 5:
                    return;
                default:
                    System.out.println("Choix invalide.");
            }
        }
    }

    private void ajouterBurger() {
        System.out.print("Nom du burger : ");
        String nom = scanner.nextLine();
        System.out.print("Prix (FCFA) : ");
        double prix = scanner.nextDouble();
        scanner.nextLine();
        System.out.print("URL de l'image (Cloudinary) : ");
        String image = scanner.nextLine();
        System.out.print("Description : ");
        String description = scanner.nextLine();

        Burger burger = new Burger(0, nom, prix, image, description);
        burgerService.ajouterBurger(burger);
        System.out.println("Burger ajouté.");
    }

    private void modifierBurger() {
        System.out.print("ID du burger à modifier : ");
        int id = Integer.parseInt(scanner.nextLine());
        Burger burger = burgerService.trouverBurgerParId(id);
        if (burger != null) {
            System.out.print("Nouveau nom : ");
            burger.setNom(scanner.nextLine());
            System.out.print("Nouveau prix (FCFA) : ");
            burger.setPrix(Double.parseDouble(scanner.nextLine()));
            System.out.print("Nouvelle URL de l'image (Cloudinary) : ");
            burger.setImage(scanner.nextLine());

            System.out.print("Nouvelle description : ");
            burger.setDescription(scanner.nextLine());
            burgerService.modifierBurger(burger);
            System.out.println("Burger modifié : " + burger);
        } else {
            System.out.println("Burger non trouvé.");
        }
    }

    private void archiverBurger() {
        System.out.print("ID du burger à archiver : ");
        int id = Integer.parseInt(scanner.nextLine());
        burgerService.archiverBurger(id);
        System.out.println("Burger archivé.");
    }

    private void listerBurgers() {
        List<Burger> burgers = burgerService.listerBurgers();
        System.out.println("================================================================================");
        System.out.printf("%-5s %-25s %-15s %-40s%n", "ID", "Nom", "Prix (FCFA)", "Description");
        System.out.println("================================================================================");
        for (Burger b : burgers) {
            System.out.printf("%-5d %-25s %-15.2f %-40s%n", b.getId(), b.getNom(), b.getPrix(), b.getDescription());
        }
        System.out.println("================================================================================");
    }

    private void ajouterComplement() {
        System.out.print("Nom du complément : ");
        String nom = scanner.nextLine();
        System.out.print("Prix (FCFA) : ");
        double prix = scanner.nextDouble();
        scanner.nextLine();
        System.out.print("URL de l'image (Cloudinary) : ");
        String image = scanner.nextLine();
        System.out.print("Description : ");
        String description = scanner.nextLine();

        Complement complement = new Complement(0, nom, prix, image, description);
        complementService.ajouterComplement(complement);
        System.out.println("Complément ajouté.");
    }

    private void modifierComplement() {
        System.out.print("ID du complément à modifier : ");
        int id = Integer.parseInt(scanner.nextLine());
        Complement complement = complementService.trouverComplementParId(id);
        if (complement != null) {
            System.out.print("Nouveau nom : ");
            complement.setNom(scanner.nextLine());
            System.out.print("Nouveau prix (FCFA) : ");
            complement.setPrix(Double.parseDouble(scanner.nextLine()));
            System.out.print("Nouvelle URL de l'image (Cloudinary) : ");
            complement.setImage(scanner.nextLine());

            System.out.print("Nouvelle description : ");
            complement.setDescription(scanner.nextLine());
            complementService.modifierComplement(complement);
            System.out.println("Complément modifié : " + complement);
        } else {
            System.out.println("Complément non trouvé.");
        }
    }

    private void archiverComplement() {
        System.out.print("ID du complément à archiver : ");
        int id = Integer.parseInt(scanner.nextLine());
        complementService.archiverComplement(id);
        System.out.println("Complément archivé.");
    }

    private void listerComplements() {
        List<Complement> complements = complementService.listerComplements();
        System.out.println("================================================================================");
        System.out.printf("%-5s %-25s %-15s %-40s%n", "ID", "Nom", "Prix (FCFA)", "Description");
        System.out.println("================================================================================");
        for (Complement c : complements) {
            System.out.printf("%-5d %-25s %-15.2f %-40s%n", c.getId(), c.getNom(), c.getPrix(), c.getDescription());
        }
        System.out.println("================================================================================");
    }

    private void ajouterMenu() {
        System.out.print("Nom du menu : ");
        String nom = scanner.nextLine();
        System.out.print("URL de l'image (Cloudinary) : ");
        String image = scanner.nextLine();
        System.out.print("Description : ");
        String description = scanner.nextLine();

        // Sélectionner un burger existant
        List<Burger> burgers = burgerService.listerBurgers();
        if (burgers.isEmpty()) {
            System.out.println("Aucun burger disponible. Créez d'abord un burger.");
            return;
        }
        System.out.println("Burgers disponibles :");
        for (Burger b : burgers) {
            System.out.println(b.getId() + " - " + b.getNom());
        }
        System.out.print("ID du burger pour le menu : ");
        int burgerId = Integer.parseInt(scanner.nextLine());
        Burger burger = burgerService.trouverBurgerParId(burgerId);
        if (burger == null) {
            System.out.println("Burger non trouvé.");
            return;
        }

        // Sélectionner des compléments
        List<Complement> complements = complementService.listerComplements();
        if (complements.isEmpty()) {
            System.out.println("Aucun complément disponible. Créez d'abord des compléments.");
            return;
        }
        System.out.println("Compléments disponibles :");
        for (Complement c : complements) {
            System.out.println(c.getId() + " - " + c.getNom());
        }
        System.out.print("Nombre de compléments : ");
        int nbComp = Integer.parseInt(scanner.nextLine());
        double prixTotal = burger.getPrix();
        for (int i = 0; i < nbComp; i++) {
            System.out.print("ID du complément " + (i + 1) + " : ");
            int compId = Integer.parseInt(scanner.nextLine());
            Complement comp = complementService.trouverComplementParId(compId);
            if (comp != null) {
                prixTotal += comp.getPrix();
            } else {
                System.out.println("Complément non trouvé, ignoré.");
            }
        }

        Menu menu = new Menu(0, nom, prixTotal, image, description);
        menuService.ajouterMenu(menu);
        System.out.println("Menu ajouté avec prix calculé : " + prixTotal + " FCFA.");
    }

    private void modifierMenu() {
        System.out.print("ID du menu à modifier : ");
        int id = Integer.parseInt(scanner.nextLine());
        Menu menu = menuService.trouverMenuParId(id);
        if (menu != null) {
            System.out.print("Nouveau nom : ");
            menu.setNom(scanner.nextLine());
            System.out.print("Nouvelle URL de l'image (Cloudinary) : ");
            menu.setImage(scanner.nextLine());

            System.out.print("Nouvelle description : ");
            menu.setDescription(scanner.nextLine());
            // Recalculer le prix si nécessaire, mais pour simplifier, on garde le même
            menuService.modifierMenu(menu);
            System.out.println("Menu modifié : " + menu);
        } else {
            System.out.println("Menu non trouvé.");
        }
    }

    private void archiverMenu() {
        System.out.print("ID du menu à archiver : ");
        int id = Integer.parseInt(scanner.nextLine());
        menuService.archiverMenu(id);
        System.out.println("Menu archivé.");
    }

    private void listerMenus() {
        List<Menu> menus = menuService.listerMenus();
        System.out.println("================================================================================");
        System.out.printf("%-5s %-25s %-15s %-40s%n", "ID", "Nom", "Prix (FCFA)", "Description");
        System.out.println("================================================================================");
        for (Menu m : menus) {
            System.out.printf("%-5d %-25s %-15.2f %-40s%n", m.getId(), m.getNom(), m.getPrix(), m.getDescription());
        }
        System.out.println("================================================================================");
    }

    public void gererMenus() {
        while (true) {
            System.out.println("\n=== Gestion des Menus ===");
            System.out.println("1. Ajouter un Menu");
            System.out.println("2. Modifier un Menu");
            System.out.println("3. Archiver un Menu");
            System.out.println("4. Lister les Menus");
            System.out.println("5. Retour");
            System.out.print("Choix : ");
            int choix = scanner.nextInt();
            scanner.nextLine(); // consommer la ligne

            switch (choix) {
                case 1:
                    ajouterMenu();
                    break;
                case 2:
                    modifierMenu();
                    break;
                case 3:
                    archiverMenu();
                    break;
                case 4:
                    listerMenus();
                    break;
                case 5:
                    return;
                default:
                    System.out.println("Choix invalide.");
            }
        }
    }

    public void gererComplements() {
        while (true) {
            System.out.println("\n=== Gestion des Compléments ===");
            System.out.println("1. Ajouter un Complément");
            System.out.println("2. Modifier un Complément");
            System.out.println("3. Archiver un Complément");
            System.out.println("4. Lister les Compléments");
            System.out.println("5. Retour");
            System.out.print("Choix : ");
            int choix = scanner.nextInt();
            scanner.nextLine(); // consommer la ligne

            switch (choix) {
                case 1:
                    ajouterComplement();
                    break;
                case 2:
                    modifierComplement();
                    break;
                case 3:
                    archiverComplement();
                    break;
                case 4:
                    listerComplements();
                    break;
                case 5:
                    return;
                default:
                    System.out.println("Choix invalide.");
            }
        }
    }

    public void demarrer() {
        while (true) {
            afficherMenu();
            int choix = scanner.nextInt();
            switch (choix) {
                case 1:
                    gererBurgers();
                    break;
                case 2:
                    gererMenus();
                    break;
                case 3:
                    gererComplements();
                    break;
                case 4:
                    System.out.println("Au revoir!");
                    return;
                default:
                    System.out.println("Choix invalide.");
            }
        }
    }
}