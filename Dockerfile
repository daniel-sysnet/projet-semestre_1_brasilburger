# -------- BUILD STAGE --------
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /app

# Copier la solution et restaurer
COPY csharp_web.sln ./
COPY csharp_web/csharp_web.csproj csharp_web/
RUN dotnet restore

# Copier le reste du projet
COPY csharp_web/. ./csharp_web/
WORKDIR /app/csharp_web

# Publier l'application
RUN dotnet publish -c Release -o /out

# -------- RUNTIME STAGE --------
FROM mcr.microsoft.com/dotnet/aspnet:8.0
WORKDIR /app

COPY --from=build /out .

# Render fournit le port via la variable PORT
ENV ASPNETCORE_URLS=http://+:$PORT

EXPOSE 10000

ENTRYPOINT ["dotnet", "csharp_web.dll"]
