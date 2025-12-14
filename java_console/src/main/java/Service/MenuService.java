package service;

import entity.Menu;
import repository.MenuRepository;
import java.util.List;

public class MenuService {
    private MenuRepository menuRepository;

    public MenuService() {
        this.menuRepository = new MenuRepository();
    }

    public void ajouterMenu(Menu menu) {
        menuRepository.save(menu);
    }

    public void modifierMenu(Menu menu) {
        menuRepository.update(menu);
    }
}