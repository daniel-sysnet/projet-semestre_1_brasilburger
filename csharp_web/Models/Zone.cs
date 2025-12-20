using System.ComponentModel.DataAnnotations;

namespace csharp_web.Models
{
    public class Zone
    {
        [Key]
        public int Id { get; set; }

        [Required]
        [StringLength(255)]
        public string Nom { get; set; }

        [Required]
        public decimal Prix { get; set; }
    }
}