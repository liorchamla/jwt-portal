declare var process: {
  env: {
    NG_APP_ENV: string;
    // Replace the line below with your environment variable for better type checking
    [key: string]: any;
    NG_APP_API_URL: string;
    NG_APP_GITPOD_WORKSPACE_URL: string;
    GITPOD_WORKSPACE_URL: string;
  };
};
