using System.Text.Json;
using Edificar.KDBridge.Models;

namespace Edificar.KDBridge.Services;

public class JsonExportService
{
    public async Task<string> ExportAsync(EdificarImportDocument document, string outputPath)
    {
        var options = new JsonSerializerOptions
        {
            WriteIndented = true,
            PropertyNamingPolicy = null
        };

        var json = JsonSerializer.Serialize(document, options);

        var directory = Path.GetDirectoryName(outputPath);

        if (!string.IsNullOrWhiteSpace(directory))
        {
            Directory.CreateDirectory(directory);
        }

        await File.WriteAllTextAsync(outputPath, json);

        return outputPath;
    }
}