using System.ComponentModel.DataAnnotations;

namespace csharp_web.Models
{
    public enum MethodePaiement
    {
        Wave,
        OM
    }

    public class Paiement
    {
        [Key]
        public int Id { get; set; }

        [Required]
        public DateTime Date { get; set; }

        [Required]
        public decimal Montant { get; set; }

        [Required]
        public MethodePaiement Methode { get; set; }
    }
}