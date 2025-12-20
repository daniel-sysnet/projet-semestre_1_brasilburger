# Stage 1 : Build
FROM mcr.microsoft.com/dotnet/sdk:9.0 AS build

WORKDIR /src

# Copier le fichier projet et restaurer les packages
COPY *.csproj .
RUN dotnet restore

# Copier le reste du projet
COPY . .

# Build + publish
RUN dotnet publish -c Release -o /app/publish

# Stage 2 : Runtime
FROM mcr.microsoft.com/dotnet/aspnet:9.0 AS runtime
WORKDIR /app
COPY --from=build /app/publish .

# Variables d'environnement pour PostgreSQL (Render)
ENV ASPNETCORE_URLS=http://+:10000
ENV DOTNET_RUNNING_IN_CONTAINER=true

# Exposer le port (Render utilise 10000 par défaut pour les services web Docker)
EXPOSE 10000

ENTRYPOINT ["dotnet", "csharp_web.dll"]
