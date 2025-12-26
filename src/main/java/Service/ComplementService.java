package Service;

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

    public void modifierComplement(Complement complement) {
        complementRepository.update(complement);
    }

    public void archiverComplement(int id) {
        complementRepository.archive(id);
    }

    public List<Complement> listerComplements() {
        return complementRepository.findAll();
    }

    public Complement trouverComplementParId(int id) {
        return complementRepository.findById(id);
    }
}
