package Service;

import entity.Burger;
import repository.BurgerRepository;
import java.util.List;

public class BurgerService {
    private BurgerRepository burgerRepository;

    public BurgerService() {
        this.burgerRepository = new BurgerRepository();
    }

    public void ajouterBurger(Burger burger) {
        burgerRepository.save(burger);
    }

    public void modifierBurger(Burger burger) {
        burgerRepository.update(burger);
    }

    public void archiverBurger(int id) {
        burgerRepository.archive(id);
    }

    public List<Burger> listerBurgers() {
        return burgerRepository.findAll();
    }

    public Burger trouverBurgerParId(int id) {
        return burgerRepository.findById(id);
    }
}
