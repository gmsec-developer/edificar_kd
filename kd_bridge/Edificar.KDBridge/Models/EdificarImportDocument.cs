using System.Text.Json.Serialization;

namespace Edificar.KDBridge.Models;

public class EdificarImportDocument
{
    [JsonPropertyName("version")]
    public string Version { get; set; } = "1.0";

    [JsonPropertyName("source")]
    public string Source { get; set; } = "kitchendraw_sdk";

    [JsonPropertyName("generated_at")]
    public DateTime GeneratedAt { get; set; } = DateTime.UtcNow;

    [JsonPropertyName("header")]
    public object Header { get; set; } = new();

    [JsonPropertyName("modules")]
    public List<ModuleItem> Modules { get; set; } = new();

    [JsonPropertyName("parts")]
    public List<PartItem> Parts { get; set; } = new();
}