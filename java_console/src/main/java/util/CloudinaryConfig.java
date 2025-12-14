package util;

import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;
import java.util.Map;

public class CloudinaryConfig {
    private static Cloudinary cloudinary;

    static {
        Map<String, String> config = ObjectUtils.asMap(
                "cloud_name", "dqwrv7p6x",
                "api_key", "786144278936889",
                "api_secret", "rgYl1EeB41ErPBU2dJOzgv3ebaw");
        cloudinary = new Cloudinary(config);
    }

    public static Cloudinary getCloudinary() {
        return cloudinary;
    }
}