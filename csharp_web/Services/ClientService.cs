using csharp_web.Models;
using csharp_web.Repositories;

namespace csharp_web.Services
{
    public class ClientService : IClientService
    {
        private readonly IClientRepository _clientRepository;

        public ClientService(IClientRepository clientRepository)
        {
            _clientRepository = clientRepository;
        }

        public async Task<Client?> AuthenticateAsync(string nom, string telephone)
        {
            return await _clientRepository.GetByNomAndTelephoneAsync(nom, telephone);
        }

        public async Task RegisterAsync(string nom, string prenom, string telephone)
        {
<<<<<<< HEAD
            // Vérifier si le téléphone existe déjà
            var existingClient = await _clientRepository.GetByTelephoneAsync(telephone);
            if (existingClient != null)
            {
                throw new InvalidOperationException($"Un client avec le numéro {telephone} existe déjà.");
            }

=======
>>>>>>> 75ff3cf8d66dd0cdd3e2ce0f872ae25463cc570c
            var client = new Client
            {
                Nom = nom,
                Prenom = prenom,
                Telephone = telephone
            };
            await _clientRepository.AddAsync(client);
        }

        public async Task<bool> IsRegisteredAsync(string nom, string telephone)
        {
            return await _clientRepository.ExistsAsync(nom, telephone);
        }
    }
}