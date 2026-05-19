using Edificar.KDBridge.Models;

namespace Edificar.KDBridge.Services;

public class KitchenDrawSdkService
{
    public EdificarImportDocument ExtractCurrentScene()
    {
        // TODO:
        // Integrar KD.SDK2 cuando tengamos la DLL oficial.
        // Flujo esperado:
        // var appli = new KD.SDK2.Appli();
        // var scene = appli.Scene;
        // Leer header, modulos, despiece y generar EdificarImportDocument.

        var document = new EdificarImportDocument
        {
            Source = "kitchendraw_sdk_placeholder"
        };

        document.Header = new
        {
            project_name = "Pendiente SDK KitchenDraw",
            client_name = "",
            designer = "",
            currency = "USD",
            total_without_tax = 0,
            total_with_tax = 0
        };

        return document;
    }
}