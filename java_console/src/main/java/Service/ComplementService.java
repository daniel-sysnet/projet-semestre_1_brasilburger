package service;

import entity.Complement;
import repository.ComplementRepository;
import java.util.List;

public class ComplementService {
    private ComplementRepository complementRepository;

    public ComplementService() {
        this.complementRepository = new ComplementRepository();
    }

    public void ajouterComplement(Complement complement) {
        complementRepository.save(complement);
    }
}
