using csharp_web.Models;

namespace csharp_web.Repositories
{
    public interface IClientRepository
    {
        Task<Client?> GetByNomAndTelephoneAsync(string nom, string telephone);
<<<<<<< HEAD
        Task<Client?> GetByTelephoneAsync(string telephone);
=======
>>>>>>> 75ff3cf8d66dd0cdd3e2ce0f872ae25463cc570c
        Task AddAsync(Client client);
        Task<bool> ExistsAsync(string nom, string telephone);
    }
}