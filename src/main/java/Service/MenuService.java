package Service;

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

    public void archiverMenu(int id) {
        menuRepository.archive(id);
    }

    public List<Menu> listerMenus() {
        return menuRepository.findAll();
    }

    public Menu trouverMenuParId(int id) {
        return menuRepository.findById(id);
    }
}