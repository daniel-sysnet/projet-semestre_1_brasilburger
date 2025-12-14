package service;

import com.cloudinary.Api;
import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;
import util.CloudinaryConfig;
import java.util.List;
import java.util.Map;
import java.util.ArrayList;

public class CloudinaryService {
    private Cloudinary cloudinary = CloudinaryConfig.getCloudinary();

    public List<String> getImagesInFolder(String folder) {
        List<String> images = new ArrayList<>();
        try {
            Api api = cloudinary.api();
            String prefix = "Brasil_Burger_ressources/" + folder + "/";
            Map result = api.resources(ObjectUtils.asMap(
                    "type", "upload",
                    "prefix", prefix,
                    "max_results", 100));
            List resources = (List) result.get("resources");
            for (Object res : resources) {
                Map resource = (Map) res;
                String publicId = (String) resource.get("public_id");
                String url = (String) resource.get("secure_url");
                images.add(publicId + " -> " + url);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return images;
    }

    public String getImageUrl(String publicId) {
        try {
            Map result = cloudinary.api().resource(publicId, ObjectUtils.emptyMap());
            return (String) result.get("secure_url");
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }
}
